import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NavController, AlertController } from '@ionic/angular';
import { CarService } from 'src/app/services/car.service';
import { Storage } from '@ionic/storage-angular';  
import { HttpErrorResponse } from '@angular/common/http';  

@Component({
  selector: 'app-agregar-vehiculo',
  templateUrl: './agregar-vehiculo.page.html',
  styleUrls: ['./agregar-vehiculo.page.scss'],
})
export class AgregarVehiculoPage implements OnInit {
  carBrands: { id: number; name: string }[] = [];
  vehicleForm: FormGroup; 
  models: { id: number; name: string }[] = []; 
  years: number[] = []; // Inicialmente vacío

  constructor(
    private formBuilder: FormBuilder,
    private navCtrl: NavController,
    private alertController: AlertController,
    private carService: CarService,
    private storageService: Storage 
  ) {
    this.vehicleForm = this.formBuilder.group({
      brand: ['', Validators.required], 
      model: [{ value: '', disabled: true }, Validators.required], 
      year: ['', Validators.required],  
      patente: ['', Validators.required], 
    });
  }

  async ngOnInit() {
    this.generateYears(); // Llamar al método para generar años
    try {
      const brands = await this.carService.getCarBrands();
      if (brands && Array.isArray(brands)) {
        this.carBrands = brands; 
        console.log('Marcas de coches cargadas:', this.carBrands);
      } else {
        console.error('Formato de marcas de coches no válido:', brands);
      }
    } catch (error) {
      console.error('Error al cargar las marcas de coches:', error);
      this.carBrands = [];
    }
  }

  // Método para generar años desde el año actual hasta 1970
  generateYears() {
    const currentYear = new Date().getFullYear();
    this.years = [];
    for (let year = currentYear; year >= 1970; year--) {
      this.years.push(year);
    }
  }

  async onBrandChange(brandId: number) {
    if (brandId) {
      this.vehicleForm.controls['model'].enable();
      try {
        const models = await this.carService.getCarModelsByBrand(brandId);
        if (models && Array.isArray(models)) {
          this.models = models;
          console.log('Modelos de coches cargados:', this.models);
        } else {
          console.error('Formato de modelos de coches no válido:', models);
        }
      } catch (error) {
        console.error('Error al cargar los modelos de coches:', error);
        this.models = [];
      }
    } else {
      this.vehicleForm.controls['model'].disable();
    }
  }

  goBack() {
    this.navCtrl.back();
  }

  async showAlert() {
    const alert = await this.alertController.create({
      header: 'Éxito',
      message: 'Vehículo agregado correctamente.',
      buttons: ['OK']
    });
    await alert.present();
  }

  async onSubmit() {
    if (this.vehicleForm.valid) {
      const { brand, model, patente, year } = this.vehicleForm.value; 
      try {
        const storedUser = await this.storageService.get('newuser');
        const owner_id = storedUser?.user?.id;  
  
        if (!owner_id) {
          throw new Error('No se encontró el owner_id en el Storage.');
        }
  
        const response: any = await this.carService.registerCar({ brand_id: brand, model_id: model, patent: patente, owner_id, year });
        console.log('Registro exitoso:', response);
  
        if (response.success === true) {
          const carBrandName = this.carBrands.find(b => b.id === brand)?.name || 'Unknown brand';
          await this.storageService.set('newcar', { brand: carBrandName, model, year, patente });
  
          await this.storageService.set('token', response.data.access_token);
          this.showAlert();
          setTimeout(() => {
            this.navCtrl.navigateForward('/mecanico/generar-servicio');
          }, 2000);
        } else {
          await this.presentAlert('Error de registro', response.message || 'No se pudo completar el registro.');
        }
      } catch (error) {
        console.error('Error en el registro:', error);
        let errorMsg = 'Error al crear vehículo'; 
        if (error instanceof HttpErrorResponse) {
          if (error.status === 404) {
            errorMsg = 'Endpoint no encontrado. Verifica la URL del servidor.';
          } else if (error.error && error.error.message) {
            errorMsg = error.error.message;
          }
        }
        this.presentAlert('Error de registro', errorMsg);
      }
    } else {
      this.presentAlert('Formulario inválido', 'Por favor, completa todos los campos requeridos.');
    }
  }
  
  async presentAlert(header: string, message: string) {
    const alert = await this.alertController.create({
      header,
      message,
      buttons: ['OK'],
    });
    await alert.present();
  }
}

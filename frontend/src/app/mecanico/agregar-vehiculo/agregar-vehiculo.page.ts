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
  models: string[] = ['Corolla', 'Civic', 'Mustang', 'Camaro']; 
  years: number[] = [2020, 2021, 2022, 2023]; 

  constructor(
    private formBuilder: FormBuilder,
    private navCtrl: NavController,
    private alertController: AlertController,
    private CarService: CarService,
    private storageService: Storage 
  ) {
    this.vehicleForm = this.formBuilder.group({
      brand: ['', Validators.required], 
      model: ['', Validators.required], 
      year: ['', Validators.required],  
      patente: ['', Validators.required], 
    });
  }

  async ngOnInit() {
    try {
      const brands = await this.CarService.getCarBrands();
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
  
        const response: any = await this.CarService.registerCar({ brand_id: brand, model, patent: patente, owner_id, year });
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

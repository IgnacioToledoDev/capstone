import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NavController, AlertController } from '@ionic/angular'; 
import { CarService } from 'src/app/services/car.service'; 

@Component({
  selector: 'app-agregar-vehiculo',
  templateUrl: './agregar-vehiculo.page.html',
  styleUrls: ['./agregar-vehiculo.page.scss'],
})
export class AgregarVehiculoPage implements OnInit {
  carBrands: string[] | undefined = [];

  vehicleForm: FormGroup;
  brands: string[] = ['Toyota', 'Honda', 'Ford', 'Chevrolet'];
  models: string[] = ['Corolla', 'Civic', 'Mustang', 'Camaro'];
  years: number[] = [2020, 2021, 2022, 2023];

  constructor(
    private formBuilder: FormBuilder,
    private navCtrl: NavController,
    private alertController: AlertController, 
    private CarService: CarService
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
      this.carBrands = brands; 
      console.log('Marcas de coches:', this.carBrands);
    } catch (error) {
      console.error('Error inesperado al cargar las marcas de coches:', error);
      this.carBrands = []; 
    }
  }
  goBack() {
    this.navCtrl.back();
  }

  // Método para mostrar la alerta
  async showAlert() {
    const alert = await this.alertController.create({
      header: 'Éxito',
      message: 'Vehículo agregado correctamente.',
      buttons: ['OK']
    });
    await alert.present();
  }

  // Método para redirigir a la página de 'mecanico/cotizar'
  goToCotizar() {
    this.navCtrl.navigateForward('/mecanico/cotizar');
  }

  onSubmit() {
    if (this.vehicleForm.valid) {
      console.log('Form Submitted', this.vehicleForm.value);
      this.showAlert();
      setTimeout(() => {
        this.goToCotizar();
      },); 
    } else {
      console.log('Form not valid');
    }
  }
}

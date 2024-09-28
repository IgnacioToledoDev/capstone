import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NavController} from '@ionic/angular';

@Component({
  selector: 'app-agregar-vehiculo',
  templateUrl: './agregar-vehiculo.page.html',
  styleUrls: ['./agregar-vehiculo.page.scss'],
})
export class AgregarVehiculoPage implements OnInit {

  vehicleForm: FormGroup;
  brands: string[] = ['Toyota', 'Honda', 'Ford', 'Chevrolet'];
  models: string[] = ['Corolla', 'Civic', 'Mustang', 'Camaro'];
  years: number[] = [2020, 2021, 2022, 2023];

  constructor(
    private formBuilder: FormBuilder,
    private navCtrl: NavController
  ) {
    this.vehicleForm = this.formBuilder.group({
      brand: ['', Validators.required],
      model: ['', Validators.required],
      year: ['', Validators.required],
      patente: ['', Validators.required],
    });
  }
  goBack() {
    this.navCtrl.back();
  }

  ngOnInit() {}

  onSubmit() {
    if (this.vehicleForm.valid) {
      console.log('Form Submitted', this.vehicleForm.value);/* RECORADAR AGRGAR ALARMA ANTES DE PASAR la validacion  */
    } else {
      console.log('Form not valid');
    }
  }
}
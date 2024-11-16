import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NavController, AlertController } from '@ionic/angular';
import { UserService } from 'src/app/services/user.service';
import { Storage } from '@ionic/storage-angular';  
import { HttpErrorResponse } from '@angular/common/http';  

@Component({
  selector: 'app-register-user',
  templateUrl: './register-user.page.html',
  styleUrls: ['./register-user.page.scss'],
})
export class RegisterUserPage implements OnInit {

  registerForm!: FormGroup;

  constructor(
    private formBuilder: FormBuilder,
    private userService: UserService,
    private navCtrl: NavController,
    private alertController: AlertController,
    private storageService: Storage 
  ) {}

  goBack() {
    this.navCtrl.back();
  }

  ngOnInit() {
    this.registerForm = this.formBuilder.group({
      email: ['', [Validators.required, Validators.email]],
      name: ['', Validators.required],                  
      lastname: ['', Validators.required],                
      rut: ['', [Validators.required, Validators.pattern(/^[0-9]+-[0-9kK]$/)]],  // Validación para rut con guion
      phone: ['', [
        Validators.required,
        Validators.pattern(/^[0-9]{9}$/), // Solo 9 dígitos numéricos
        Validators.minLength(9),
        Validators.maxLength(9)
      ]], // Validación para solo 9 dígitos numéricos
    });
  }
  
  async onSubmit() {
    if (this.registerForm.valid) {
      const { email, name, lastname, rut, phone } = this.registerForm.value; 
      try {
        const response: any = await this.userService.register({ email, name, lastname, rut, phone });
        console.log('Registro exitoso:', response);

        if (response.success === true) {
          let userData = response.data;

          await this.storageService.set('token', userData.access_token);

          this.navCtrl.navigateForward('mecanico/agregar-vehiculo');
        } else {
          await this.presentAlert('Error de registro', response.error.message || 'No se pudo completar el registro.');
        }
      } catch (error) {
        console.error('Error en el registro:', error);

        let errorMsg = 'Error al crear Cliente'; 
        if (error instanceof HttpErrorResponse) {
          if (error.error && error.error.message) {
            errorMsg = error.error.message;
          } else if (error.status === 404) {
            errorMsg = 'Endpoint no encontrado. Verifica la URL del servidor.';
          }
        }
        this.presentAlert('Error de registro', errorMsg);
      }
    } else {
      this.presentAlert('Formulario inválido', 'Por favor, completa todos los campos requeridos correctamente.');
    }
  }
  validatePhoneLength(event: any) {
    const input = event.target as HTMLInputElement;
    if (input.value.length > 9) {
      input.value = input.value.slice(0, 9); // Limita a 9 caracteres
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

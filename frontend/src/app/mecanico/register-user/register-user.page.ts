import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { NavController, AlertController } from '@ionic/angular';
import { UserService } from 'src/app/services/user.service';
import { Storage } from '@ionic/storage-angular';  // Importamos el Storage

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
    private storageService: Storage // Inyectamos el Storage
  ) {}

  goBack() {
    this.navCtrl.back();
  }

  ngOnInit() {
    this.registerForm = this.formBuilder.group({
      email: ['', [Validators.required, Validators.email]],
      username: ['', Validators.required],                  
      lastname: ['', Validators.required],                
      rut: ['', Validators.required],                     
      phoneNumber: ['', [Validators.required, Validators.pattern('^[0-9]+$')]], 
    });
  }

  async onSubmit() {
    if (this.registerForm.valid) {
      const { email, username, lastname, rut, phoneNumber } = this.registerForm.value; 
      try {
        const response: any = await this.userService.register({ email, username, lastname, rut, phoneNumber });
        console.log('Registro exitoso:', response);

        if (response.success === true) {
          let userData = response.data;

          // Almacenamos el token en el Storage
          await this.storageService.set('token', userData.access_token);

          // Redirigimos a la página del mecánico
          this.navCtrl.navigateForward('/mecanico/home-mecanico');
        } else {
          // Si el registro falla, mostramos una alerta
          await this.presentAlert('Error de registro', 'No se pudo completar el registro.');
        }
      } catch (error) {
        console.error('Error en el registro:', error);
        this.presentAlert('Error de registro', 'Error al crear Cliente');
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

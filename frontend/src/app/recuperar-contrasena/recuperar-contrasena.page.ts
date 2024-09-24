import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { UserService } from 'src/app/services/user.service';
import { NavController, AlertController } from '@ionic/angular';

@Component({
  selector: 'app-recuperar-contrasena',
  templateUrl: './recuperar-contrasena.page.html',
  styleUrls: ['./recuperar-contrasena.page.scss'],
})
export class RecuperarContrasenaPage implements OnInit {
  recoverForm!: FormGroup;

  constructor(
    private fb: FormBuilder,
    private userService: UserService,
    private navCtrl: NavController,
    private alertController: AlertController
  ) {}

  ngOnInit() {
    this.recoverForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
    });
  }

  async onSubmit() {
    if (this.recoverForm.valid) {
      const { email } = this.recoverForm.value;
      try {
        const response = await this.userService.recovery({ email });
        console.log('Correo de recuperación enviado:', response);
        await this.presentAlert('Éxito', 'Se ha enviado un correo para recuperar la contraseña.');
        this.navCtrl.navigateForward('/inicio-sesion');  
      } catch (error) {
        console.error('Error al recuperar contraseña:', error);
        await this.presentAlert('Error', 'No se pudo enviar el correo de recuperación. Intenta nuevamente.');
      }
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

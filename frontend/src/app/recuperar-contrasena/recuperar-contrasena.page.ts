import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AlertController } from '@ionic/angular';

@Component({
  selector: 'app-recuperar-contrasena',
  templateUrl: './recuperar-contrasena.page.html',
  styleUrls: ['./recuperar-contrasena.page.scss'],
})
export class RecuperarContrasenaPage implements OnInit {
  email: string;

  constructor(private router: Router, private alertController: AlertController) {
    this.email = ''; 
  }

  ngOnInit() {
  }

  async recuperarContrasena() {
    const alert = await this.alertController.create({
      header: 'Recuperar Contraseña',
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel',
          cssClass: 'secondary',
          handler: () => {
            console.log('Recuperación cancelada');
          }
        }, {
          text: 'Enviar',
          handler: async () => {
            console.log('Correo enviado a:', this.email);
            // Aquí puedes agregar la lógica para enviar el correo de recuperación
            const successAlert = await this.alertController.create({
              header: 'Éxito',
              message: 'El mensaje se ha enviado correctamente.',
              buttons: ['OK']
            });
            await successAlert.present();
            this.router.navigate(['/inicio-sesion']);
          }
        }
      ]
    });

    await alert.present();
  }
}

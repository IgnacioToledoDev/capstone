import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AlertController } from '@ionic/angular';

@Component({
  selector: 'app-terminos-condiciones',
  templateUrl: './terminos-condiciones.component.html',
  styleUrls: ['./terminos-condiciones.component.scss'],
})
export class TerminosCondicionesComponent implements OnInit {

  constructor(private router: Router, private alertController: AlertController) { }

  ngOnInit() {}

  async aceptarTerminos() {
    const alert = await this.alertController.create({
      header: 'Términos y Condiciones',
      message: 'Por favor, acepta nuestros términos y condiciones para continuar.',
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel',
          cssClass: 'secondary',
          handler: () => {
            console.log('Términos no aceptados');
          }
        }, {
          text: 'Aceptar',
          handler: () => {
            console.log('Términos aceptados');
            this.router.navigate(['/bienvenidos']);
          }
        }
      ]
    });

    await alert.present();
  }
}

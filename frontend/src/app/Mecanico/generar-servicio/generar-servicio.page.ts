import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';


@Component({
  selector: 'app-generar-servicio',
  templateUrl: './generar-servicio.page.html',
  styleUrls: ['./generar-servicio.page.scss'],
})
export class GenerarServicioPage implements OnInit {

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController
  ) {}

  ngOnInit() {
  }
  goBack() {
    this.navCtrl.back();
  }

  async presentAlert() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿Estás seguro de querer guardar?',
      backdropDismiss: true, // Permite cerrar la alerta al presionar fuera
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel',
          handler: () => {
            console.log('Acción cancelada');
          },
        },
        {
          text: 'Aceptar',
          handler: () => {
            console.log('Acción aceptada');
            this.navCtrl.navigateForward('/mecanico/cotizar');
          },
        },
      ],
    });

    await alert.present();
  }
}
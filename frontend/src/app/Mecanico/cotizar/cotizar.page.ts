import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';

@Component({
  selector: 'app-cotizar',
  templateUrl: './cotizar.page.html',
  styleUrls: ['./cotizar.page.scss'],
})
export class CotizarPage implements OnInit {

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController
  ) {}

  ngOnInit() {}

  goBack() {
    this.navCtrl.back();
  }

  async presentAlert() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿Estás seguro de querer guardar la Cotización?',
      backdropDismiss: true, 
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
          handler: async () => {
            console.log('Acción aceptada');
            await this.presentConfirmationAlert();
            this.navCtrl.navigateForward('/mecanico/home-mecanico');
          },
        },
      ],
    });

    await alert.present();
  }

  async presentConfirmationAlert() {
    const confirmationAlert = await this.alertController.create({
      header: 'Éxito',
      message: 'La cotización ha sido creada exitosamente.',
      buttons: ['OK'],
    });

    await confirmationAlert.present();
  }
}
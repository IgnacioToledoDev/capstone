import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';

@Component({
  selector: 'app-info-ser-cli',
  templateUrl: './info-ser-cli.page.html',
  styleUrls: ['./info-ser-cli.page.scss'],
})
export class InfoSerCliPage implements OnInit {


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
      message: '¿estás seguro de querer iniciar el servicio?',
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
            this.navCtrl.navigateForward('/mecanico/seguimiento');
          },
        },
      ],
    });

    await alert.present();
  }

}
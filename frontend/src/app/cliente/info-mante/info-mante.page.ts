import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';

@Component({
  selector: 'app-info-mante',
  templateUrl: './info-mante.page.html',
  styleUrls: ['./info-mante.page.scss'],
})
export class InfoMantePage implements OnInit {



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
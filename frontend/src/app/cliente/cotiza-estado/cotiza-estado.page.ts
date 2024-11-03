import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { Quotation } from 'src/app/intefaces/catiza'; 

@Component({
  selector: 'app-cotiza-estado',
  templateUrl: './cotiza-estado.page.html',
  styleUrls: ['./cotiza-estado.page.scss'],
})
export class CotizaEstadoPage implements OnInit {

  selectedQuotation: Quotation | null = null;

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storage: Storage
  ) {
    this.initStorage();
  }

  async initStorage() {
    await this.storage.create(); // Initializes the storage
  }

  async ngOnInit() {
    await this.loadSelectedQuotation();
  }

  async loadSelectedQuotation() {
    this.selectedQuotation = await this.storage.get('selectedQuotation');
    console.log('Loaded quotation:', this.selectedQuotation);
  }

  goBack() {
    this.navCtrl.back();
  }

  async presentAlert() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿Estás seguro de querer aceptar la cotización?',
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
            this.navCtrl.navigateForward('/mecanico/info-ser-cli');
          },
        },
      ],
    });

    await alert.present();
  }

  async presentAlertRe() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿Estás seguro de querer rechazar la cotización?',
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
            this.navCtrl.navigateForward('/mecanico/home-mecanico');
          },
        },
      ],
    });

    await alert.present();
  }
}

import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { Quotation } from 'src/app/intefaces/catiza'; 
import { CotizaService } from 'src/app/services/cotiza.service';

@Component({
  selector: 'app-cotiza-estado',
  templateUrl: './cotiza-estado.page.html',
  styleUrls: ['./cotiza-estado.page.scss'],
})
export class CotizaEstadoPage implements OnInit {
  selectedQuotation: any = {}; 

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storage: Storage,
    private cotizaService: CotizaService,
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
    console.log('Loaded quotation:', this.selectedQuotation?.quotation.id);
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
            await this.cotizaService.approveQuotation(this.selectedQuotation?.quotation.id);
            console.log('Acción aceptada');
            this.navCtrl.navigateForward('/cliente/home-cliente');
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
            await this.cotizaService.declineQuotation(this.selectedQuotation.quotation.id);
            console.log('Acción aceptada ');
            this.navCtrl.navigateForward('/cliente/home-cliente');
          },
        },
      ],
    });

    await alert.present();
  }
}

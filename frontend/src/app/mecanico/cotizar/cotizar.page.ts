import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';

@Component({
  selector: 'app-cotizar',
  templateUrl: './cotizar.page.html',
  styleUrls: ['./cotizar.page.scss'],
})
export class CotizarPage implements OnInit {
  user: any = {};
  car: any = {};
  selectedServices: { id: number, name: string, price: number }[] = [];
  deletedServices: { id: number, name: string, price: number }[] = [];

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage
  ) {}

  async ngOnInit() {
    await this.storageService.create();

    const userData = await this.storageService.get('newuser');
    if (userData && userData.user) {
      this.user = userData.user;
    }

    const carData = await this.storageService.get('newcar');
    if (carData) {
      this.car = carData;
    }

    const storedServices = await this.storageService.get('servi_coti');
    if (storedServices) {
      this.selectedServices = storedServices;
    }
  }

  goBack() {
    this.navCtrl.back();
  }

  async presentAlert() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿Estás seguro de querer Enviar la Cotización?',
      backdropDismiss: true,
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel',
        },
        {
          text: 'Aceptar',
          handler: async () => {
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
      message: 'La cotización ha sido creada y enviada exitosamente.',
      buttons: ['OK'],
    });

    await confirmationAlert.present();
  }

  async removeService(service: { id: number; name: string; price: number }) {
    this.selectedServices = this.selectedServices.filter(s => s.id !== service.id);
    this.deletedServices.push(service);

    await this.storageService.set('servi_coti', this.selectedServices);
  }

  async restoreService(service: { id: number; name: string; price: number }) {
    this.deletedServices = this.deletedServices.filter(s => s.id !== service.id);
    this.selectedServices.push(service);

    await this.storageService.set('servi_coti', this.selectedServices);
  }

  calculateTotal(): number {
    return this.selectedServices.reduce((total, service) => total + service.price, 0);
  }
}

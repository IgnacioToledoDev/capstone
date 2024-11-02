import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';

@Component({
  selector: 'app-aprobar-cotiza',
  templateUrl: './aprobar-cotiza.page.html',
  styleUrls: ['./aprobar-cotiza.page.scss'],
})
export class AprobarCotizaPage implements OnInit {
  user: any = {};
  car: any = {};
  selectedServices: { id: number, name: string, price: number }[] = [];
  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage,
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
      console.log('Modelos de coches cargados:', this.car);
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
      message: '¿estás seguro de querer Aceptar la cotizasion?',
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
  async presentAlertre() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿estás seguro de querer Rechazar la cotizasion?',
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
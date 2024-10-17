import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular'; // Importar Storage

@Component({
  selector: 'app-cotizar',
  templateUrl: './cotizar.page.html',
  styleUrls: ['./cotizar.page.scss'],
})
export class CotizarPage implements OnInit {
  user: any = {};  // Para guardar los datos del usuario
  car: any = {};   // Para guardar los datos del coche

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage // Inyectar Storage
  ) {}

  async ngOnInit() {
    // Asegúrate de inicializar el almacenamiento
    await this.storageService.create();

    // Obtener datos del usuario del Storage
    const userData = await this.storageService.get('newuser');
    if (userData && userData.user) {
      this.user = userData.user;
      console.log('Datos del usuario almacenados en "newuser":', this.user); // Mostrar en consola
    } else {
      console.log('No se encontró el usuario en el Storage');
    }

    // Obtener datos del coche del Storage
    const carData = await this.storageService.get('newcar');
    if (carData) {
      this.car = carData;
      console.log('Datos del coche almacenados en "newcar":', this.car); // Mostrar en consola
    } else {
      console.log('No se encontró el coche en el Storage');
    }
  }

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

import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';

@Component({
  selector: 'app-qrinfo',
  templateUrl: './qrinfo.page.html',
  styleUrls: ['./qrinfo.page.scss'],
})
export class QrinfoPage implements OnInit {
  userData: any; // Variable para almacenar los datos del usuario desde el Storage
  hasNewCar: any; // Bandera para determinar si hay datos de 'newcar'

  constructor(
    private navCtrl: NavController,
    private storageService: Storage, // Servicio de Storage para acceder a los datos
    private alertCtrl: AlertController
  ) {}

  async ngOnInit() {
    // Inicializar el Storage
    await this.storageService.create();

    // Cargar los datos del usuario desde el Storage
    this.userData = await this.storageService.get('userDataQR');

    // Verificar si existen datos en 'newcar'
    const newCar = await this.storageService.get('newcar');
    this.hasNewCar = newCar

    if (this.userData && this.userData.user && this.userData.user.id) {
      const userId = this.userData.user.id;
      await this.storageService.set('userIdQR', userId); // Guardar solo el id en el Storage
      console.log('ID del usuario guardado en Storage:', userId);
    } else {
      console.log('No se encontraron datos para "userDataQR".');
    }
  }

  goBack() {
    this.navCtrl.back();
  }

  navigateToMaintenance() {
    if (this.hasNewCar) {
      this.navCtrl.navigateForward('/mecanico/generar-servicio'); // Cambiar la ruta según sea necesario
    } else {
      this.navCtrl.navigateForward('/mecanico/liscarclinte');
    }
  }

}

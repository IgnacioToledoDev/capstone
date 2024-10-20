import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular'; 



@Component({
  selector: 'app-generar-servicio',
  templateUrl: './generar-servicio.page.html',
  styleUrls: ['./generar-servicio.page.scss'],
})
export class GenerarServicioPage implements OnInit {
  user: any = {};  
  car: any = {}; 

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
      console.log('Datos del usuario almacenados en "newuser":', this.user); 
    } else {
      console.log('No se encontró el usuario en el Storage');
    }

    const carData = await this.storageService.get('newcar');
    if (carData) {
      this.car = carData;
      console.log('Datos del coche almacenados en "newcar":', this.car); 
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
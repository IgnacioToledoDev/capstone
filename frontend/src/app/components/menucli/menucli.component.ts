import { Component, OnInit } from '@angular/core';
import { NavController, AlertController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';


@Component({
  selector: 'app-menucli',
  templateUrl: './menucli.component.html',
  styleUrls: ['./menucli.component.scss'],
})
export class MenucliComponent  implements OnInit {

  constructor(
    private navCtrl: NavController, 
    private storage: Storage, 
    private alertController: AlertController
  ) { }

  async ngOnInit() {
    await this.storage.create(); // Asegura que el Storage está inicializado
  }
  
  async cerrarSesion() {
    // Borra todos los datos almacenados en el Storage
    await this.storage.clear();
    console.log('Sesión cerrada y almacenamiento borrado');

    // Redirige al usuario a la página de inicio de sesión
    this.navCtrl.navigateRoot('/inicio-sesion');
  }

  async presentLogoutConfirm() {
    const alert = await this.alertController.create({
      header: 'Confirmar',
      message: '¿Estás seguro de que deseas cerrar la sesión?',
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel',
          handler: () => {
            console.log('Cierre de sesión cancelado');
          }
        },
        {
          text: 'Cerrar Sesión',
          handler: () => {
            this.cerrarSesion(); 
          }
        }
      ]
    });

    await alert.present();
  }

  async historial() {
    console.log('Historial');

    this.navCtrl.navigateRoot('/cliente/mante-histo');
  }
  async escanear_patente() {
    console.log('escanear_patente');

    this.navCtrl.navigateRoot('/mecanico/escanear-patente');
  }
  async escanear_qr() {
    console.log('escanear_qr');

    this.navCtrl.navigateRoot('/mecanico/escanear-qr');
  }
  async ajustes() {
    console.log('ajustes');
    this.navCtrl.navigateRoot('/mecanico/ajustes');
  }
}

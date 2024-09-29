import { Component, OnInit } from '@angular/core';
import { NavController, AlertController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';


@Component({
  selector: 'app-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.scss'],
})
export class MenuComponent  implements OnInit {

  constructor(private navCtrl: NavController, private storage: Storage , private alertController: AlertController,) { }

  ngOnInit() {}
  
  async cerrarSesion() {
    console.log('Sesión cerrada');

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
    console.log('historial');

    this.navCtrl.navigateRoot('/mecanico/historial');
  }
}
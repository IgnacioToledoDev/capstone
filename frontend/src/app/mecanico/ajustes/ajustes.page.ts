import { Component, OnInit } from '@angular/core';
import { NavController, AlertController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { UserService } from 'src/app/services/user.service';


@Component({
  selector: 'app-ajustes',
  templateUrl: './ajustes.page.html',
  styleUrls: ['./ajustes.page.scss'],
})
export class AjustesPage implements OnInit {
  token: string | null = null;  
  user: any = {};    
  
  goBack() {
    this.navCtrl.back();
  }

  constructor(
    private navCtrl: NavController, 
    private storage: Storage, 
    private alertController: AlertController,
    private userService: UserService
  ) { }

  async ngOnInit() {
    await this.storage.create(); 
    const sessionData = await this.userService.getUserSession();

    if (sessionData) {
      this.token = sessionData.token;  
      this.user = sessionData.user;    

      console.log('Token:', this.token);
      console.log('User Info:', this.user);
    } else {
      console.log('No se encontraron datos de sesión.');
    }
  }
  
  async cerrarSesion() {
    await this.storage.clear();
    console.log('Sesión cerrada y almacenamiento borrado');
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
}
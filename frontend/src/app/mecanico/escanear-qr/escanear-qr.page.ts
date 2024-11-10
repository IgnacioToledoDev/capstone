import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { SeguimientoService } from 'src/app/services/seguimiento.service';  // Asegúrate de importar el servicio correctamente

@Component({
  selector: 'app-escanear-qr',
  templateUrl: './escanear-qr.page.html',
  styleUrls: ['./escanear-qr.page.scss'],
})
export class EscanearQrPage implements OnInit {

  rut: string = '';  // Variable para almacenar el RUT ingresado

  constructor(
    private alertController: AlertController, // para más adelante
    private navCtrl: NavController,
    private storageService: Storage, // para más adelante
    private userService: SeguimientoService // Servicio para obtener los datos del usuario
  ) {}

  ngOnInit() {
    // Inicializar el Storage
    this.storageService.create();
  }

  // Función que se ejecuta cuando el usuario confirma el RUT
  async submitRUT() {
    if (this.rut) {
      // Llamar al servicio para obtener los datos del usuario por el RUT
      const usuario = await this.userService.getUserByRut(this.rut);
      if (usuario) {
        console.log('Usuario obtenido:', usuario);
        this.navCtrl.navigateForward('/mecanico/qrinfo');
      } else {
        console.log('No se pudo obtener el usuario.');
      }
    } else {
      this.showAlert('Por favor, ingrese un RUT válido.');
    }
  }

  // Muestra una alerta si el RUT no es válido
  async showAlert(message: string) {
    const alert = await this.alertController.create({
      header: 'Error',
      message: message,
      buttons: ['OK']
    });

    await alert.present();
  }

  goBack() {
    this.navCtrl.back();
  }
}

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
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage,
    private userService: SeguimientoService // Servicio para obtener los datos del usuario
  ) {}

  ngOnInit() {
    // Inicializar el Storage
    this.storageService.create();
  }

  // Función para validar el RUT
  validateRUT(rut: string): boolean {
    rut = rut.replace(/\s+/g, ''); // Eliminar espacios en blanco
    const rutPattern = /^\d{7,8}-[0-9kK]{1}$/; // Expresión regular para el formato general del RUT
    if (!rutPattern.test(rut)) {
      return false; // Si el RUT no cumple con el patrón
    }

    const body = rut.slice(0, -2); // Parte numérica del RUT
    const verifier = rut.slice(-1).toUpperCase(); // Dígito verificador

    let sum = 0;
    let factor = 2;
    for (let i = body.length - 1; i >= 0; i--) {
      sum += parseInt(body.charAt(i)) * factor;
      factor = factor === 7 ? 2 : factor + 1;
    }

    const mod = sum % 11;
    const calculatedVerifier = mod === 1 ? 'K' : (11 - mod).toString();

    return verifier === calculatedVerifier; // Comparar el dígito verificador
  }

  // Función que se ejecuta cuando el usuario confirma el RUT
  async submitRUT() {
    if (this.rut && this.validateRUT(this.rut)) {
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

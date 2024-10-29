import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { CotizaService } from 'src/app/services/cotiza.service'; // Asegúrate de importar tu servicio aquí

@Component({
  selector: 'app-generar-servicio',
  templateUrl: './generar-servicio.page.html',
  styleUrls: ['./generar-servicio.page.scss'],
})
export class GenerarServicioPage implements OnInit {
  user: any = {};
  car: any = {};
  services: { id: number, name: string, price: number }[] = [];
  selectedServices: { id: number, name: string, price: number }[] = [];

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage,
    private cotizaService: CotizaService // Inyectamos el servicio
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

    // Obtén los servicios desde el servicio CotizaService
    this.services = await this.cotizaService.getCarServices();
  }

  goBack() {
    this.navCtrl.back();
  }

  async presentAlert() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿Estás seguro de querer guardar?',
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
          handler: () => {
            console.log('Acción aceptada');
            // Aquí puedes implementar la lógica para guardar la cotización
            this.navCtrl.navigateForward('/mecanico/cotizar');
          },
        },
      ],
    });

    await alert.present();
  }

  addService(service: { id: number, name: string, price: number }) {
    this.selectedServices.push(service);
  }

  removeService(service: { id: number, name: string, price: number }) {
    this.selectedServices = this.selectedServices.filter(s => s.id !== service.id);
  }

  calculateTotal(): number {
    return this.selectedServices.reduce((total, service) => total + service.price, 0);
  }
}

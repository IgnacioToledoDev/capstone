import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { CotizaService } from 'src/app/services/cotiza.service';

@Component({
  selector: 'app-generar-servicio',
  templateUrl: './generar-servicio.page.html',
  styleUrls: ['./generar-servicio.page.scss'],
})
export class GenerarServicioPage implements OnInit {
  user: any = {};
  car: any = {};
  services: { id: number, name: string, price: number }[] = [];
  filteredServices: { id: number, name: string, price: number }[] = [];
  selectedServices: { id: number, name: string, price: number }[] = [];

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage,
    private cotizaService: CotizaService
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
    }

    this.services = await this.cotizaService.getCarServices();
    this.filteredServices = [...this.services];
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
        },
        {
          text: 'Aceptar',
          handler: async () => {
            await this.guardarCotizacion();
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

  filterServices(event: any) {
    const searchTerm = event.target.value.toLowerCase();
    this.filteredServices = this.services.filter(service => 
      service.name.toLowerCase().includes(searchTerm)
    );
  }

  async guardarCotizacion() {
    await this.storageService.set('servi_coti', this.selectedServices);
  }
}

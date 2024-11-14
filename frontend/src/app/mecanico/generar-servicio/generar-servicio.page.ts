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
  services: { id: number, name: string, description: string, type_id: number, price: number }[] = [];
  filteredServices: { id: number, name: string, description: string, type_id: number, price: number }[] = [];
  selectedServices: { id: number, name: string, price: number }[] = [];
  serviceTypes: { id: number, name: string }[] = [];


  pageSize: number = 7;
  currentPage: number = 0;
  pagedServices: { id: number, name: string, description: string, type_id: number, price: number }[][] = [];

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
      console.log('Datos del coche almacenados temporalmente:', this.car);
    }

    // Fetch services and service types
    this.services = await this.cotizaService.getCarServices();
    this.serviceTypes = await this.cotizaService.getServiceTypes();

    // Inicializar los servicios filtrados por "Servicios" (por defecto)
    this.filteredServices = this.services.filter(service => service.type_id === 1); // 1 es el ID para "Servicios"
    
    // Dividir los servicios filtrados en bloques de 7
    this.pagedServices = this.chunkArray(this.filteredServices, this.pageSize);
  }

  chunkArray(array: any[], size: number) {
    const result = [];
    for (let i = 0; i < array.length; i += size) {
      result.push(array.slice(i, i + size));
    }
    return result;
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
    const exists = this.selectedServices.some(s => s.id === service.id);
    if (!exists) {
      this.selectedServices.push(service);
    } else {
      this.presentAlertServiceExists();
    }
  }

  async presentAlertServiceExists() {
    const alert = await this.alertController.create({
      header: 'Servicio Duplicado',
      message: 'Este servicio ya ha sido agregado a la lista.',
      buttons: ['Aceptar'],
    });
    await alert.present();
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
    this.pagedServices = this.chunkArray(this.filteredServices, this.pageSize);
  }

  filterByType(typeId: number) {
    // Filtrar servicios por tipo
    this.filteredServices = this.services.filter(service => service.type_id === typeId);
    this.pagedServices = this.chunkArray(this.filteredServices, this.pageSize);
  }

  async guardarCotizacion() {
    await this.storageService.set('servi_coti', this.selectedServices);
  }
}

import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
import { CarService } from 'src/app/services/car.service';
import { StorageService } from 'src/app/services/storage.service';

@Component({
  selector: 'app-agenda-car-lis',
  templateUrl: './agenda-car-lis.page.html',
  styleUrls: ['./agenda-car-lis.page.scss'],
})
export class AgendaCarLisPage implements OnInit {
  carList: { marca: string, patente: string, modelo: string, year: number }[] = [];
  CarObjects: { marca: string, patente: string, modelo: string, year: number }[] = [];

  constructor(
    private navCtrl: NavController,
    private carService: CarService,
    private storageService: StorageService
  ) { }

  async ngOnInit() {
    await this.loadCars();
  }

  async loadCars() {
    try {
      const cars = await this.carService.getCars();
      this.carList = cars.map(car => ({
        id: car.id,
        marca: car.brand,
        patente: this.formatPatent(car.patent),
        modelo: car.model,
        year: car.year
      }));
      this.CarObjects = this.carList; // Inicializa la lista filtrada
    } catch (error) {
      console.error('Error al cargar los coches:', error);
    }
  }

  filterByPatente(event: any) {
    const query = event.target.value.toLowerCase();
    this.CarObjects = this.carList.filter(car => car.patente.toLowerCase().includes(query));
  }

  goBack() {
    this.navCtrl.back();
  }

  formatPatent(patent: string | null) {
    if (!patent) {
      return '';
    }

    return patent.slice(0, 3) + ' ' + patent.slice(3);
  }


  selectCar(car: any) {
    this.storageService.set('carSelected', car);
    console.log('Carro seleccionado:', car);
    this.navCtrl.navigateForward('/cliente/agendar');
  }
}

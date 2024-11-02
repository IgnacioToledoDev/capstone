import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
import { CarService } from 'src/app/services/car.service';

@Component({
  selector: 'app-agenda-car-lis',
  templateUrl: './agenda-car-lis.page.html',
  styleUrls: ['./agenda-car-lis.page.scss'],
})
export class AgendaCarLisPage implements OnInit {
  eventos: { marca: string, patente: string, modelo: string, year: number }[] = [];
  filteredEventos: { marca: string, patente: string, modelo: string, year: number }[] = [];

  constructor(
    private navCtrl: NavController,
    private carService: CarService
  ) { }

  async ngOnInit() {
    await this.loadCars();
  }

  async loadCars() {
    try {
      const cars = await this.carService.getCars();
      this.eventos = cars.map(car => ({
        marca: car.brand,
        patente: car.patent,
        modelo: car.model,
        year: car.year
      }));
      this.filteredEventos = this.eventos; // Inicializa la lista filtrada
    } catch (error) {
      console.error('Error al cargar los coches:', error);
    }
  }

  filterByPatente(event: any) {
    const query = event.target.value.toLowerCase();
    this.filteredEventos = this.eventos.filter(car => car.patente.toLowerCase().includes(query));
  }

  goBack() {
    this.navCtrl.back();
  }
}
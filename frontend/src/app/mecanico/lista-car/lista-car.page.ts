import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
import { CarService } from 'src/app/services/car.service';
import { Storage } from '@ionic/storage-angular'; // Importa el servicio de Storage

@Component({
  selector: 'app-lista-car',
  templateUrl: './lista-car.page.html',
  styleUrls: ['./lista-car.page.scss'],
})
export class ListaCarPage implements OnInit {
  eventos: { marca: string, patente: string, modelo: string, year: number }[] = [];
  filteredEventos: { marca: string, patente: string, modelo: string, year: number }[] = [];
  userRole: string = '';

  constructor(
    private navCtrl: NavController,
    private carService: CarService,
    private storage: Storage // Inyecta el servicio de Storage
  ) { }

  async ngOnInit() {
    await this.loadCars();
    // No es necesario hacer la verificación aquí si solo la quieres en el botón
  }

  // Función para cargar los vehículos
  async loadCars() {
    try {
      const cars = await this.carService.getCars();
      this.eventos = cars.map(car => ({
        marca: car.brand,
        patente: car.patent,
        modelo: car.model,
        year: car.year
      }));
      console.log(this.eventos);
      this.filteredEventos = this.eventos; // Inicializa la lista filtrada
    } catch (error) {
      console.error('Error al cargar los coches:', error);
    }
  }

  // Función para verificar los datos almacenados y redirigir
  async checkStoredDataAndRedirect() {
    const storedData = await this.storage.get('datos'); // Obtén los datos del storage
    if (storedData) {
      this.userRole = storedData.roles; // Asigna el rol del usuario
      console.log('Datos encontrados en Storage:', storedData);

      // Redirige según el rol del usuario
      if (this.userRole === 'CUSTOMER_USER') {
        this.navCtrl.navigateRoot('/cliente/home-cliente'); // Redirige a home-cliente si el rol es CUSTOMER_USER
      } else {
        this.navCtrl.navigateRoot('/mecanico/home-mecanico'); // Redirige a home-mecanico si el rol no es CUSTOMER_USER
      }
    } else {
      console.log('No se encontraron datos en Storage');
    }
  }

  // Función para filtrar por patente
  filterByPatente(event: any) {
    const query = event.target.value.toLowerCase();
    this.filteredEventos = this.eventos.filter(car => car.patente.toLowerCase().includes(query));
  }

  // Función para volver atrás
  goBack() {
    this.navCtrl.back();
  }

  // Función que se ejecuta al hacer clic en el botón de "Inicio"
  onHomeButtonClick() {
    this.checkStoredDataAndRedirect(); // Verifica el rol y redirige al usuario
  }
}

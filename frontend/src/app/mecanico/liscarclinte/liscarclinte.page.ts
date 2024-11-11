import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
import { CarService } from 'src/app/services/car.service';
import { Storage } from '@ionic/storage-angular';

@Component({
  selector: 'app-liscarclinte',
  templateUrl: './liscarclinte.page.html',
  styleUrls: ['./liscarclinte.page.scss'],
})
export class LiscarclintePage implements OnInit {
  cars: { brand: string,id: number, patent: string, model: string, year: number }[] = [];
  filteredCars: { brand: string, id: number,patent: string, model: string, year: number }[] = [];

  constructor(
    private navCtrl: NavController,
    private carService: CarService,
    private storage: Storage
  ) { }

  async ngOnInit() {
    await this.loadCars();
  }

  async loadCars() {
    try {
      // Obtener el userId desde el almacenamiento
      const userId = await this.storage.get('userIdQR');
      const userQR = await this.storage.get('userDataQR');
      
      if (userId) {
        // Llamar al servicio usando el userId obtenido
        const cars = await this.carService.getUserCars(userId);
        this.cars = cars.map((car: any) => ({
          brand: car.brand,
          id: car.id,
          patent: car.patent,
          model: car.model,
          year: car.year
        }));
        this.filteredCars = this.cars;
      } else {
        console.error('No se encontró userIdQR en el almacenamiento');
      }
    } catch (error) {
      console.error('Error al cargar los coches:', error);
    }
  }

  filterByPatente(event: any) {
    const query = event.target.value.toLowerCase();
    this.filteredCars = this.cars.filter(car => car.patent.toLowerCase().includes(query));
  }

  // Función para seleccionar el coche, guardarlo en el almacenamiento y redirigir
  async selectCar(car: any) {
    try {
      // Obtener los datos del usuario desde el almacenamiento
      const userQR = await this.storage.get('userDataQR');
      
      if (userQR) {
        // Guardar el coche y los datos del usuario en el almacenamiento
        await this.storage.set('newcar', car);
        await this.storage.set('newuser', userQR);

        console.log('Coche seleccionado:', car);
        console.log('Datos del usuario:', userQR);

        // Redirigir a la página 'generar-servicio'
        this.navCtrl.navigateForward('/mecanico/generar-servicio');
      } else {
        console.error('No se encontraron los datos del usuario');
      }
    } catch (error) {
      console.error('Error al guardar el coche y el usuario:', error);
    }
  }

  goBack() {
    this.navCtrl.back();
  }
}

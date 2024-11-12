import { Component, OnInit } from '@angular/core';
import { ManteciService } from 'src/app/services/manteci.service';  // Asegúrate de que el servicio está importado correctamente
import { Storage } from '@ionic/storage-angular'; // Asegúrate de tener la dependencia de Ionic Storage
import { AlertController, NavController } from '@ionic/angular';

@Component({
  selector: 'app-mante-histo',
  templateUrl: './mante-histo.page.html',
  styleUrls: ['./mante-histo.page.scss'],
})
export class ManteHistoPage implements OnInit {
  maintenances: any[] = [];
  filteredMaintenances: any[] = [];
  userId: number = -1;
  token: string = '';

  constructor(private maintenanceService: ManteciService, private storage: Storage,private navCtrl: NavController,) { }

  ngOnInit() {
    this.loadUserData();
  }

  // Cargar los datos del usuario y el historial de mantenimientos
  async loadUserData() {
    try {
      const userData = await this.storage.get('datos');
      if (userData && userData.user) {
        this.token = userData.token;
        this.userId = userData.user.id;
        console.log('User ID:', this.userId);
        console.log('Token:', this.token);

        // Obtener el historial de mantenimientos
        const history = await this.maintenanceService.getMaintenanceHistoryByUserId(this.userId);
        console.log('Mantenimientos históricos del usuario:', history);

        if (history && history.maintenances && Array.isArray(history.maintenances)) {
          this.maintenances = history.maintenances.map((maintenance: any) => ({
            car: maintenance.car,
            details: maintenance.details,
            services: maintenance.services,
            maintenance: maintenance.maintenance,
            mechanic: maintenance.mechanic
          }));

          // Almacenar la lista filtrada inicial
          this.filteredMaintenances = [...this.maintenances];
        } else {
          console.log('No se encontraron mantenimientos');
        }
      } else {
        console.log('User data not found in storage');
      }
    } catch (error) {
      console.log('Error al obtener el historial de mantenimiento:', error);
    }
  }

  // Filtrar mantenimientos según la búsqueda
  filterMaintenance(event: any) {
    const searchTerm = event.target.value?.toLowerCase();
    if (searchTerm) {
      // Asegúrate de que los campos sean válidos para la búsqueda
      this.filteredMaintenances = this.maintenances.filter(maintenance =>
        maintenance.car?.brand?.toLowerCase().includes(searchTerm) ||
        maintenance.car?.model?.toLowerCase().includes(searchTerm) ||
        maintenance.car?.patent?.toLowerCase().includes(searchTerm) ||
        maintenance.services?.some((service: any) => service.name?.toLowerCase().includes(searchTerm))
      );
    } else {
      this.filteredMaintenances = [...this.maintenances]; // Restablecer al estado inicial
    }
  }

  // Función para volver a la página anterior
  goBack() {
    // Lógica para regresar a la página anterior
    history.back();
  }

  // Función para guardar los datos de la mantención seleccionada en Storage
  async saveMaintenanceToStorage(maintenance: any) {
    try {
      // Guardar los datos en Storage bajo la clave 'hitomancli'
      await this.storage.set('maintenanceIdcli', maintenance.maintenance.id);
      console.log('Mantención guardada en el Storage', maintenance.maintenance.id);
      this.navCtrl.navigateForward('/mecanico/info-car');
      // Puedes redirigir a otra página si es necesario
    } catch (error) {
      console.error('Error al guardar la mantención en el Storage:', error);
    }
  }
}

import { Component, OnInit } from '@angular/core';
import { ManteciService } from 'src/app/services/manteci.service';
import { Storage } from '@ionic/storage-angular';
import { NavController } from '@ionic/angular';
import { Router } from '@angular/router';

@Component({
  selector: 'app-mantesnclien',
  templateUrl: './mantesnclien.page.html',
  styleUrls: ['./mantesnclien.page.scss'],
})
export class MantesnclienPage implements OnInit {
  mainten: any[] = [];
  filteredMaintenances: any[] = [];

  constructor(
    private manteciService: ManteciService,
    private storageService: Storage,
    private navCtrl: NavController,
    private router: Router
  ) {}

  async ngOnInit() {
    await this.storageService.create();
    this.loadMaintenances();
  }

  async loadMaintenances() {
    const userId = await this.storageService.get('userIdQR');
    
    if (userId) {
      const maintenances = await this.manteciService.getAllMaintenanceByUser(userId);
      if (maintenances) {
        this.mainten = maintenances.maintenances;
        this.filteredMaintenances = this.mainten;
      } else {
        console.log('No se encontraron mantenimientos para este usuario.');
      }
    } else {
      console.log('No se encontró el ID de usuario.');
    }
  }

  filterMaintenances(event: any) {
    const query = event.target.value.toLowerCase();
    this.filteredMaintenances = this.mainten.filter(maintenance => 
      maintenance.name.toLowerCase().includes(query) || maintenance.date.includes(query)
    );
  }

  goBack() {
    this.navCtrl.back();
  }

  // Modificación en el método para verificar el estado antes de navegar
  async saveMaintenanceIdAndNavigate(maintenanceId: number, statusId: number) {
    // Verificar que el estado no sea "Finalizado" (statusId === 4)
    if (statusId !== 4) {
      await this.storageService.set('idmantesion', maintenanceId); // Guardar el ID de mantenimiento
      this.router.navigate(['/mecanico/info-ser-cli']); // Navegar a la página deseada
    }
  }

  formatDate(dateString: string): { date: string, time: string } {
    const dateObj = new Date(dateString);
    const date = dateObj.toLocaleDateString(); 
    const time = dateObj.toLocaleTimeString();  
    return { date, time };
  }

  // Función para devolver el texto y el icono basado en el status_id
  getStatusLabel(statusId: number) {
    switch (statusId) {
      case 1:
        return { label: 'Inactivo', icon: 'stop-circle-outline', color: 'medium' };
      case 2:
        return { label: 'Empezado', icon: 'play-circle-outline', color: 'primary' };
      case 3:
        return { label: 'En Progreso', icon: 'hourglass-outline', color: 'warning' };
      case 4:
        return { label: 'Finalizado', icon: 'checkmark-circle-outline', color: 'success' };
      default:
        return { label: 'Estado desconocido', icon: 'help-circle-outline', color: 'light' };
    }
  }
}

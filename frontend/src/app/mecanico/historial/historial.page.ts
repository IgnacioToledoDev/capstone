import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { ManteciService } from 'src/app/services/manteci.service'; 


@Component({
  selector: 'app-historial',
  templateUrl: './historial.page.html',
  styleUrls: ['./historial.page.scss'],
})
export class HistorialPage implements OnInit {

  eventos: { 
    id: number,
    name: string,
    status_id: number,
    recommendation_action: string,
    pricing: number,
    car_id: number,
    mechanic_id: number,
    start_maintenance: string,
    end_maintenance: string 
  }[] = [];

  filteredEventos = this.eventos;

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private manteciService: ManteciService
  ) { }

  ngOnInit() {
    this.loadMaintenanceHistory();
  }

  async loadMaintenanceHistory() {
    try {
      const response = await this.manteciService.getMaintenanceHistorical();
      this.eventos = response.map((entry) => ({
        id: entry.id,
        name: entry.name,
        status_id: entry.status_id,
        recommendation_action: entry.recommendation_action,
        pricing: entry.pricing,
        car_id: entry.car_id,
        mechanic_id: entry.mechanic_id,
        start_maintenance: entry.start_maintenance,
        end_maintenance: entry.end_maintenance
      }));
      this.filteredEventos = [...this.eventos]; // Initialize filtered list
    } catch (error) {
      console.error('Error loading maintenance history:', error);
    }
  }

  onSearchChange(event: any) {
    const searchTerm = event.target.value.toLowerCase();
    this.filteredEventos = this.eventos.filter(evento =>
      evento.name.toLowerCase().includes(searchTerm)
    );
  }

  goBack() {
    this.navCtrl.back();
  }
}
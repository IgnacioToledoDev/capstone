import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { ManteciService } from 'src/app/services/manteci.service';
import { HistoricalEntry } from 'src/app/intefaces/car';  // Importa la interfaz HistoricalEntry

@Component({
  selector: 'app-historial',
  templateUrl: './historial.page.html',
  styleUrls: ['./historial.page.scss'],
})
export class HistorialPage implements OnInit {

  eventos: HistoricalEntry[] = []; // Usa la interfaz HistoricalEntry HistoricalEntry
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
      this.eventos = response; // La respuesta ya incluye los datos de `car` y `owner`
      this.filteredEventos = [...this.eventos]; // Inicializar lista filtrada
      console.log(this.filteredEventos)
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

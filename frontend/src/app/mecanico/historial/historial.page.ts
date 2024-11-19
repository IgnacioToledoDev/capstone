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

  eventos: HistoricalEntry[] = [];
  filteredEventos: HistoricalEntry[] = [];
  currentPage: number = 1;
  itemsPerPage: number = 5;

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
      this.eventos = response;
      this.filteredEventos = [...this.eventos];
    } catch (error) {
      console.error('Error loading maintenance history:', error);
    }
  }

  onSearchChange(event: any) {
    const searchTerm = event.target.value.toLowerCase();
    this.filteredEventos = this.eventos.filter(evento =>
      evento.owner.name.toLowerCase().includes(searchTerm)
    );
    this.currentPage = 1; // Reiniciar a la primera página después de buscar
  }

  getPaginatedEventos(): HistoricalEntry[] {
    const startIndex = (this.currentPage - 1) * this.itemsPerPage;
    const endIndex = startIndex + this.itemsPerPage;
    return this.filteredEventos.slice(startIndex, endIndex);
  }

  nextPage() {
    if (this.hasNextPage()) {
      this.currentPage++;
    }
  }

  previousPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
    }
  }

  hasNextPage(): boolean {
    return this.currentPage * this.itemsPerPage < this.filteredEventos.length;
  }

  goBack() {
    this.navCtrl.back();
  }
}

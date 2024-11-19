import { Component, OnInit } from '@angular/core';
import { ReservationService } from 'src/app/services/reservation.service';
import { Storage } from '@ionic/storage'; // Importar Ionic Storage
import { AlertController, NavController } from '@ionic/angular';

@Component({
  selector: 'app-lis-reservas',
  templateUrl: './lis-reservas.page.html',
  styleUrls: ['./lis-reservas.page.scss'],
})
export class LisReservasPage implements OnInit {
  reservations: any[] = []; // Lista completa de reservas
  filteredReservations: any[] = []; // Lista filtrada
  paginatedReservations: any[] = []; // Reservas mostradas en la página actual
  currentPage: number = 1; // Página actual
  itemsPerPage: number = 5; // Número de reservas por página

  constructor(
    private reservationService: ReservationService,
    private storage: Storage,
    private navCtrl: NavController,
  ) {}

  async ngOnInit() {
    await this.loadReservations();
  }

  // Obtener el ID del mecánico desde el almacenamiento
  async getMechanicId(): Promise<number> {
    try {
      const userData = await this.storage.get('datos'); // Obtener el objeto userData desde Ionic Storage
      if (userData && userData.user.id) {
        return userData.user.id; // Retornar el ID del mecánico
      } else {
        throw new Error('No se pudo encontrar el ID del mecánico en el almacenamiento');
      }
    } catch (error) {
      console.error('Error al obtener el ID del mecánico:', error);
      return 0; // Valor por defecto en caso de error
    }
  }

  async loadReservations() {
    try {
      const mechanicId = await this.getMechanicId();
      if (mechanicId) {
        this.reservations = await this.reservationService.getReservationsByMechanicId(mechanicId);
        this.filteredReservations = [...this.reservations];
        this.updatePaginatedReservations(); // Actualiza las reservas paginadas
        console.log('Reservas obtenidas:', this.reservations);
      } else {
        console.error('No se pudo cargar las reservas, ID de mecánico no válido.');
      }
    } catch (error) {
      console.error('Error al cargar reservas:', error);
    }
  }

  updatePaginatedReservations() {
    const startIndex = (this.currentPage - 1) * this.itemsPerPage;
    const endIndex = startIndex + this.itemsPerPage;
    this.paginatedReservations = this.filteredReservations.slice(startIndex, endIndex);
  }

  filterReservations(event: any) {
    const searchTerm = event.target.value?.toLowerCase().trim();
    if (!searchTerm) {
      this.filteredReservations = [...this.reservations];
    } else {
      this.filteredReservations = this.reservations.filter(reservation =>
        reservation.client.name.toLowerCase().includes(searchTerm) ||
        reservation.car.patent.toLowerCase().includes(searchTerm)
      );
    }
    this.currentPage = 1; // Reinicia a la primera página
    this.updatePaginatedReservations(); // Actualiza la paginación
  }

  nextPage() {
    const totalPages = Math.ceil(this.filteredReservations.length / this.itemsPerPage);
    if (this.currentPage < totalPages) {
      this.currentPage++;
      this.updatePaginatedReservations();
    }
  }

  previousPage() {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.updatePaginatedReservations();
    }
  }

  // Guardar el ID de la cotización (si es necesario)
  async saveQuotationId(reservation: any) {
    console.log(`Reserva seleccionada:`, reservation);
    await this.storage.set('selectedReservation', reservation); // Guardar toda la reserva en Storage mecanico/approreserva
    console.log('Reserva guardada en Storage:', reservation);
    this.navCtrl.navigateForward('/mecanico/approreserva');
  }

  goBack() {
    this.navCtrl.back();
  }
}

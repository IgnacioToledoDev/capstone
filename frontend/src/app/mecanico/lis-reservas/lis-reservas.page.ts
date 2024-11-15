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
        console.log('Reservas obtenidas:', this.reservations);
      } else {
        console.error('No se pudo cargar las reservas, ID de mecánico no válido.');
      }
    } catch (error) {
      console.error('Error al cargar reservas:', error);
    }
  }

  // Filtrar las reservas por nombre del cliente o patente del vehículo
  filterReservations(event: any) {
    const searchTerm = event.target.value.toLowerCase();
    this.filteredReservations = this.reservations.filter((reservation) =>
      reservation.client.name.toLowerCase().includes(searchTerm) ||
      reservation.car.licensePlate.toLowerCase().includes(searchTerm)
    );
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

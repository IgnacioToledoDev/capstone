import { Component, OnInit } from '@angular/core';
import { Storage } from '@ionic/storage-angular';
import { NavController, AlertController } from '@ionic/angular';
import { ReservationService } from 'src/app/services/reservation.service';  // Adjust the import path

@Component({
  selector: 'app-approreserva',
  templateUrl: './approreserva.page.html',
  styleUrls: ['./approreserva.page.scss'],
})
export class ApproreservaPage implements OnInit {
  reservation: any = null; // Property to store reservation data

  constructor(
    private storage: Storage,
    private navCtrl: NavController,
    private approvalService: ReservationService, // Inject the approval service
    private alertController: AlertController
  ) {}

  ngOnInit() {
    this.initializeStorage(); // Ensure storage is initialized before use
  }

  // Method to initialize the storage and load reservation data
  async initializeStorage() {
    await this.storage.create(); // This initializes the storage
    this.loadReservation(); // Load the reservation data after initialization
  }

  // Method to load reservation data from Storage
  async loadReservation() {
    const storedReservation = await this.storage.get('selectedReservation');
    if (storedReservation) {
      this.reservation = storedReservation; // Assign the data to the reservation property
      console.log('Reserva cargada desde Storage:', this.reservation);
    } else {
      console.log('No se encontró ninguna reserva en Storage');
    }
  }

  // Method to approve the reservation
  async approveReservation() {
    if (!this.reservation) {
      console.log('No reservation data found');
      return;
    }

    const reservationId = this.reservation.reservation.id; // Get the reservation ID
    try {
      const success = await this.approvalService.approveReservation(reservationId); // Call the approve method from the service
      if (success) {
        this.showAlert('Reserva aprobada', 'La reserva ha sido aprobada exitosamente.');
        this.navCtrl.back(); // Navigate back after approval
      } else {
        this.showAlert('Error', 'Hubo un problema al aprobar la reserva.');
      }
    } catch (error) {
      console.error('Error al aprobar la reserva:', error);
      this.showAlert('Error', 'Hubo un error al aprobar la reserva.');
    }
  }
  async declineReservation() {
    if (!this.reservation) {
      console.log('No reservation data found');
      return;
    }

    const reservationId = this.reservation.reservation.id; // Get the reservation ID
    try {
      const success = await this.approvalService.declineReservation(reservationId); // Call the approve method from the service
      if (success) {
        this.showAlert('Reserva aprobada', 'La reserva ha sido aprobada exitosamente.');
        this.navCtrl.back(); // Navigate back after approval
      } else {
        this.showAlert('Error', 'Hubo un problema al aprobar la reserva.');
      }
    } catch (error) {
      console.error('Error al aprobar la reserva:', error);
      this.showAlert('Error', 'Hubo un error al aprobar la reserva.');
    }
  }

  // Method to show an alert message
  async showAlert(header: string, message: string) {
    const alert = await this.alertController.create({
      header,
      message,
      buttons: ['OK'],
    });

    await alert.present();
  }

  goBack() {
    this.navCtrl.back();
  }
}

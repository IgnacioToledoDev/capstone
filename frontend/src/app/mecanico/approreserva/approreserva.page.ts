import { Component, OnInit } from '@angular/core';
import { Storage } from '@ionic/storage-angular';
import { AlertController, NavController } from '@ionic/angular';

@Component({
  selector: 'app-approreserva',
  templateUrl: './approreserva.page.html',
  styleUrls: ['./approreserva.page.scss'],
})
export class ApproreservaPage implements OnInit {
  reservation: any = null; // Property to store reservation data

  constructor(private storage: Storage,private navCtrl: NavController,) {}

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

  goBack() {
    this.navCtrl.back();
  }
}

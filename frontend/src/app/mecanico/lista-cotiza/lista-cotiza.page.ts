import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { CotizaService } from 'src/app/services/cotiza.service';

@Component({
  selector: 'app-lista-cotiza',
  templateUrl: './lista-cotiza.page.html',
  styleUrls: ['./lista-cotiza.page.scss'],
})
export class ListaCotizaPage implements OnInit {
  user: any = {};
  quotations: any[] = [];
  filteredQuotations: any[] = [];

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage,
    private cotizaService: CotizaService
  ) {}

  async ngOnInit() {
    await this.storageService.create();
    
    // Retrieve user data from storage
    const userData = await this.storageService.get('datos');
    if (userData && userData.user) {
      this.user = userData.user;

      // Call the service method with the mechanic's ID
      if (this.user.id) {
        this.getQuotationsByMechanic(this.user.id);
      } else {
        console.error('No se encontró el ID del mecánico.');
      }
    }
  }

  async getQuotationsByMechanic(mechanicId: number) {
    try {
      // Call the service method and set the quotations data
      this.quotations = await this.cotizaService.getQuotationsByMechanic(mechanicId);
      this.filteredQuotations = [...this.quotations]; // Initialize filtered quotations
      console.log('Cotizaciones obtenidas:', this.quotations);
    } catch (error) {
      console.error('Error al obtener las cotizaciones:', error);
    }
  }

  // Save Quotation ID to Storage
  async saveQuotationId(id: number) {
    await this.storageService.set('id-cotiza', id);
    console.log('ID de cotización guardado:', id);
    
    // Navigate to the next page
    this.navCtrl.navigateForward('/mecanico/aprobar-cotiza');
  }

  // Filter Quotations based on Search Input
  filterQuotations(event: any) {
    const searchTerm = event.target.value.toLowerCase();

    if (!searchTerm) {
      // If no search term, reset filteredQuotations to all quotations
      this.filteredQuotations = [...this.quotations];
    } else {
      // Filter quotations based on client name, RUT, or patent
      this.filteredQuotations = this.quotations.filter(quotation =>
        quotation.client.name.toLowerCase().includes(searchTerm) ||
        quotation.client.lastname.toLowerCase().includes(searchTerm) ||
        quotation.client.rut.toLowerCase().includes(searchTerm) ||
        quotation.car.patent.toLowerCase().includes(searchTerm) 
      );
    }
  }

  goBack() {
    this.navCtrl.back();
  }
}

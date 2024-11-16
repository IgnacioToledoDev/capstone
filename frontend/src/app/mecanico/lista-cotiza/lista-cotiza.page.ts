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
    
    const userData = await this.storageService.get('datos');
    if (userData && userData.user) {
      this.user = userData.user;

      if (this.user.id) {
        this.getQuotationsByMechanic(this.user.id);
      } else {
        console.error('No se encontró el ID del mecánico.');
      }
    }
  }

  async getQuotationsByMechanic(mechanicId: number) {
    try {
      this.quotations = await this.cotizaService.getQuotationsByMechanic(mechanicId);
      this.filteredQuotations = [...this.quotations]; 
      console.log('Cotizaciones obtenidas:', this.quotations);
    } catch (error) {
      console.error('Error al obtener las cotizaciones:', error);
    }
  }

  // Save Quotation ID and Client Data to Storage
  async saveQuotationId(id: number, clientData: any) {
    await this.storageService.set('id-cotiza', id);
    await this.storageService.set('newuser', clientData); // Save client data
    console.log('ID de cotización guardado:', id);
    console.log('Datos del cliente guardados:', clientData);
    
    this.navCtrl.navigateForward('/mecanico/aprobar-cotiza');
  }

  filterQuotations(event: any) {
    const searchTerm = event.target.value.toLowerCase();

    if (!searchTerm) {
      this.filteredQuotations = [...this.quotations];
    } else {
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

import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { CotizaService } from 'src/app/services/cotiza.service';

@Component({
  selector: 'app-liscotcli',
  templateUrl: './lisquoticli.page.html',
  styleUrls: ['./lisquoticli.page.scss'],
})
export class LisquoticliPage implements OnInit {
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

    // Obtenemos el 'userIdQR' desde el almacenamiento
    const userIdQR = await this.storageService.get('userIdQR');
    if (userIdQR) {
      // Usamos 'userIdQR' para obtener las cotizaciones
      this.getQuotationsByClient(userIdQR);
      console.log('ID de cliente (userIdQR):', userIdQR);
    } else {
      console.error('No se encontró el ID del cliente en el almacenamiento.');
    }
  }

  // Obtener cotizaciones por cliente usando el ID del cliente
  async getQuotationsByClient(userId: number) {
    try {
      this.quotations = await this.cotizaService.getAllQuotationsByUser(userId);
      this.filteredQuotations = [...this.quotations];
      console.log('Cotizaciones obtenidas:', this.quotations);
    } catch (error) {
      console.error('Error al obtener las cotizaciones:', error);
    }
  }

  // Guardar ID de cotización y datos del cliente en el almacenamiento
  async saveQuotationId(id: number, clientData: any) {
    await this.storageService.set('id-cotiza', id);
    await this.storageService.set('client-data', clientData); // Guardar datos del cliente
    console.log('ID de cotización guardado:', id);
    console.log('Datos del cliente guardados:', clientData);

    this.navCtrl.navigateForward('/mecanico/aprobar-cotiza');
  }

  // Filtrar cotizaciones
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

  // Regresar a la página anterior
  goBack() {
    this.navCtrl.back();
  }
}

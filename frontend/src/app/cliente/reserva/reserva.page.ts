import { Component, OnInit } from '@angular/core';
import { CotizaService } from 'src/app/services/cotiza.service';
import { AlertController, NavController } from '@ionic/angular';
import { Quotation } from 'src/app/intefaces/catiza'; 
import { Storage } from '@ionic/storage-angular';


@Component({
  selector: 'app-reserva',
  templateUrl: './reserva.page.html',
  styleUrls: ['./reserva.page.scss'],
})
export class ReservaPage implements OnInit {

  quotations: Quotation[] = []; // Holds the list of quotations
  originalQuotations: Quotation[] = []; // Stores the original quotations for filtering

  constructor(
    private cotizaService: CotizaService,
    private navCtrl: NavController,
    private storage: Storage
  ) {
    this.initStorage();
  }

  async initStorage() {
    await this.storage.create(); // Initializes the storage
  }

  ngOnInit() {
    this.loadQuotations();
  }

  // Loads quotations from the CotizaService
  async loadQuotations() {
    this.quotations = await this.cotizaService.getQuotations();
    this.originalQuotations = [...this.quotations]; // Stores original quotations for filtering
    console.log('Cotizaciones cargadas:', this.quotations);
  }

  // Navigates back to the previous page
  goBack() {
    this.navCtrl.back();
  }

  // Saves the selected quotation to Ionic Storage
  async saveQuotation(quotation: Quotation) {
    await this.storage.set('selectedQuotation', quotation);
    console.log('Quotation saved:', quotation);
    this.navCtrl.navigateForward('/cliente/cotiza-estado');
  }
}

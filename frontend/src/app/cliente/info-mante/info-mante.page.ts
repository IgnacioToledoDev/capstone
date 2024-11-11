import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { ManteciService } from 'src/app/services/manteci.service';  // Import your service here

@Component({
  selector: 'app-info-mante',
  templateUrl: './info-mante.page.html',
  styleUrls: ['./info-mante.page.scss'],
})
export class InfoMantePage implements OnInit {


  maintenanceId: number | null = null;
  maintenanceDetails: any = null;
  serviceList: string[] = [];

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage,
    private manteciService: ManteciService  // Inject the service
  ) {}

  async ngOnInit() {
    // Initialize the storage service
    await this.storageService.create();

    // Retrieve the maintenance ID from storage
    const storedMaintenanceId = await this.storageService.get('maintenanceIdcli');
    if (storedMaintenanceId) {
      this.maintenanceId = storedMaintenanceId;
      console.log('ID de mantenimiento recuperado:', this.maintenanceId);

      // Ensure maintenanceId is not null before calling the service
      if (this.maintenanceId !== null) {
        this.fetchMaintenanceDetails(this.maintenanceId);
      }
    } else {
      console.log('No se encontró un ID de mantenimiento en Storage');
    }
  }

  // Fetch maintenance details using the retrieved maintenance ID
  async fetchMaintenanceDetails(maintenanceId: number) {
    const details = await this.manteciService.getMaintenanceDetails(maintenanceId);
    if (details) {
      this.maintenanceDetails = details;
      this.serviceList = details.services || {};
      console.log('Detalles de mantenimiento obtenidos:', this.maintenanceDetails);
      console.log('Detalles de mantenimiento obtenidos:', this.serviceList);
    } else {
      console.error('No se pudieron obtener los detalles del mantenimiento');
    }
  }

  goBack() {
    this.navCtrl.back();
  }

}

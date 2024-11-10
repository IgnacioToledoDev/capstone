import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { ManteciService } from 'src/app/services/manteci.service';

@Component({
  selector: 'app-seguimiento-cli',
  templateUrl: './seguimiento-cli.page.html',
  styleUrls: ['./seguimiento-cli.page.scss'],
})
export class SeguimientoCliPage implements OnInit {

  maintenanceId: number | null = null;
  maintenanceStatus: any = null;

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage,
    private manteciService: ManteciService
  ) {}

  async ngOnInit() {
    await this.storageService.create();

    const storedMaintenanceId = await this.storageService.get('maintenanceIdcli');
    if (storedMaintenanceId) {
      this.maintenanceId = storedMaintenanceId;
      console.log('ID de mantenimiento recuperado:', this.maintenanceId);
      await this.loadMaintenanceStatus();
    } else {
      console.log('No se encontró un ID de mantenimiento en Storage');
    }
  }

  async loadMaintenanceStatus() {
    if (this.maintenanceId !== null) {
      try {
        this.maintenanceStatus = await this.manteciService.getMaintenanceStatus(this.maintenanceId);
        console.log('Estado de mantenimiento:', this.maintenanceStatus.status);
      } catch (error) {
        console.error('Error al cargar el estado de mantenimiento:', error);
        this.maintenanceStatus = { status: 'Error loading status' };
      }
    }
  }

  goBack() {
    this.navCtrl.back();
  }


}

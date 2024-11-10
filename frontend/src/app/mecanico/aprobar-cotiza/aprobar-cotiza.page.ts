import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { CotizaService } from 'src/app/services/cotiza.service';
import { ManteciService } from 'src/app/services/manteci.service';

@Component({
  selector: 'app-aprobar-cotiza',
  templateUrl: './aprobar-cotiza.page.html',
  styleUrls: ['./aprobar-cotiza.page.scss'],
})
export class AprobarCotizaPage implements OnInit {
  user: any = {};
  quotation: any = {}; 
  listServices: any = [];
  recommendationNote: string = '';

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage,
    private cotizaService: CotizaService,
    private manteciService: ManteciService
  ) {}

  async ngOnInit() {
    await this.storageService.create();

    const quotationId = await this.storageService.get('id-cotiza');
    if (quotationId) {
      console.log('ID de cotización guardado:', quotationId);
      await this.loadQuotation(quotationId);
    }

    const carData = await this.storageService.get('client-data');
    if (carData) {
      this.user = carData;
      console.log('cliente guardado:', this.user);
    }
  }

  async loadQuotation(quotationId: number) { 
    try {
      this.quotation = await this.cotizaService.getQuotationById(quotationId);
      console.log('Cotizaciones obtenidas:', this.quotation.servicesApprovedByClient);
      for (let key in this.quotation.servicesApprovedByClient) {
        if (this.quotation.servicesApprovedByClient.hasOwnProperty(key)) {
            const service = this.quotation.servicesApprovedByClient[key];
            console.log(`Service ${key} Name: ${service.name}`);
            this.listServices.push(service);
        }
      }
      console.log(this.listServices);
      console.log('Cotizaciones obtenidas:', this.quotation); 
    } catch (error) {
      console.error('Error al obtener las cotizaciones:', error);
    }
  }

  goBack() {
    this.navCtrl.back();
  }

  async presentAlert() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿Estás seguro de querer aceptar la cotización?',
      backdropDismiss: true,
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel',
          handler: () => {
            console.log('Acción cancelada');
          },
        },
        {
          text: 'Aceptar',
          handler: async () => {
            await this.cotizaService.approveQuotation(this.quotation.quotation.id);
            console.log('Cotización aceptada');
             // Crear el registro de mantenimiento
    
            const startNowAlert = await this.alertController.create({
              header: 'Confirmación de inicio',
              message: `¿Iniciar ahora? `,
              buttons: [
                {
                  text: 'Cancelar',
                  role: 'cancel',
                  handler: async () => {
                    console.log('Acción cancelada en inicio');
                    await this.createMaintenanceRecordarechasa(); // Llamar a la función de rechazo si se cancela
  
                    // Mostrar alerta de mantenimiento creado correctamente si no es inicio inmediato
                    if (!this.quotation.startNow) {
                      const createdAlert = await this.alertController.create({
                        header: 'Mantenimiento Creado',
                        message: 'El registro de mantenimiento ha sido creado correctamente.',
                        buttons: [
                          {
                            text: 'Aceptar',
                            handler: () => {
                              console.log('Redirigiendo a home-mecanico...');
                              this.navCtrl.navigateForward('/mecanico/home-mecanico'); // Redirigir a home-mecanico
                            },
                          },
                        ],
                      });
                      await createdAlert.present(); // Presentar la alerta
                    }
                  },
                },
                {
                  text: 'Aceptar',
                  handler: async () => {
                    console.log(`El mantenimiento se iniciará ${this.quotation.startNow ? 'inmediatamente' : 'en el futuro'}`);
                    await this.createMaintenanceRecordaproba();
                    
                    this.navCtrl.navigateForward('/mecanico/info-ser-cli');
                  },
                },
              ],
            });
    
            await startNowAlert.present();
          },
        },
      ],
    });
  
    await alert.present();
  }
  
  
  

  async createMaintenanceRecordaproba() {
    const maintenanceData = {
      carId: this.quotation.car.id,
      recommendation_action: this.recommendationNote,
      services: this.listServices.map((service: { id: number }) => ({ id: service.id })), 
      startNow: true
    };

    try {
      const response = await this.manteciService.createMaintenanceRecord(maintenanceData);
      if (response) {
        console.log('Registro de mantenimiento creado con éxito:', response);
      } else {
        console.error('No se pudo crear el registro de mantenimiento.');
      }
    } catch (error) {
      console.error('Error al crear el registro de mantenimiento:', error);
    }
  }

  async createMaintenanceRecordarechasa() {
    const maintenanceData = {
      carId: this.quotation.car.id,
      recommendation_action: this.recommendationNote,
      services: this.listServices.map((service: { id: number }) => ({ id: service.id })), 
      startNow: false
    };

    try {
      const response = await this.manteciService.createMaintenanceRecord(maintenanceData);
      if (response) {
        console.log('Registro de mantenimiento creado con éxito:', response);
      } else {
        console.error('No se pudo crear el registro de mantenimiento.');
      }
    } catch (error) {
      console.error('Error al crear el registro de mantenimiento:', error);
    }
  }

  async presentAlertre() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿estás seguro de querer rechazar la cotización?',
      backdropDismiss: true,
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel',
          handler: () => {
            console.log('Acción cancelada');
          },
        },
        {
          text: 'Aceptar',
          handler: async () => {
            await this.cotizaService.declineQuotation(this.quotation.quotation.id);
            console.log('Cotización rechazada');
            this.navCtrl.navigateForward('/mecanico/home-mecanico');
          },
        },
      ],
    });

    await alert.present();
  }
}

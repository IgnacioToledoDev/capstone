import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import { NavController } from '@ionic/angular';
import { ManteciService } from 'src/app/services/manteci.service';  // Import your maintenance service

@Component({
  selector: 'app-home-cliente',
  templateUrl: './home-cliente.page.html',
  styleUrls: ['./home-cliente.page.scss'],
})
export class HomeClientePage implements OnInit {

  eventos: { nombre: string, hora: string, patente: string }[] = [
    { nombre: 'jose herera', hora: '10:00 AM', patente: 'ABC-0834' },
    { nombre: 'isaac bravo', hora: '12:00 PM', patente: 'AAC-8634' },
    { nombre: 'Nacho jara', hora: '1:00 PM', patente: 'AHG-6434' }
  ];
  username: string = '';
  token: string | null = null;
  user: any = {};
  maintenanceData: any = {}; // Store the fetched maintenance data

  constructor(
    private userService: UserService,
    private maintenanceService: ManteciService,  // Inject the MaintenanceService
    private navCtrl: NavController
  ) { }

  async ngOnInit() {
    const sessionData = await this.userService.getUserSession();

    if (sessionData) {
      this.token = sessionData.token;
      this.user = sessionData.user;
      this.username = this.capitalizeFirstLetter(this.user.name);
      console.log('Token:', this.token);
      console.log('User Info:', this.user);

      // Fetch maintenance data for the user
      await this.getMaintenanceInCourse();
    } else {
      console.log('No se encontraron datos de sesión.');
    }
  }

  async getMaintenanceInCourse() {
    try {
      const response = await this.maintenanceService.getMaintenanceInCourse(); // Call the service
      if (response) {
        this.maintenanceData = response;
        console.log('Maintenance Data:', this.maintenanceData);
      } else {
        console.error('No maintenance data found.');
      }
    } catch (error) {
      console.error('Error fetching maintenance data:', error);
    }
  }

  goBack() {
    this.navCtrl.back();
  }

  capitalizeFirstLetter(val: string) {
    return String(val).charAt(0).toUpperCase() + String(val).slice(1);
  }
}

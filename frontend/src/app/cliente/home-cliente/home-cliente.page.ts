import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import { NavController } from '@ionic/angular';
import { ManteciService } from 'src/app/services/manteci.service'; 
import { Storage } from '@ionic/storage-angular';

@Component({
  selector: 'app-home-cliente',
  templateUrl: './home-cliente.page.html',
  styleUrls: ['./home-cliente.page.scss'],
})
export class HomeClientePage implements OnInit {

  username: string = '';
  token: string | null = null;
  user: any = {};
  maintenanceData: any = {}; // Store the fetched maintenance data
  noMaintenanceInCourse: boolean = false; // Variable to handle the no maintenance case

  constructor(
    private userService: UserService,
    private maintenanceService: ManteciService,  // Inject the MaintenanceService
    private navCtrl: NavController,
    private storage: Storage  // Inject Storage
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
      if (response && response.maintenance) {
        this.maintenanceData = response;
        console.log('Maintenance Data:', this.maintenanceData);

        // Save the maintenance ID in Ionic Storage
        const maintenanceId = this.maintenanceData.maintenance.id;
        await this.storage.set('maintenanceIdcli', maintenanceId);
        console.log('Maintenance ID stored in Ionic Storage:', maintenanceId);

        // Hide the "No maintenance in course" message
        this.noMaintenanceInCourse = false;
      } else {
        console.error('No maintenance data found.');
        this.noMaintenanceInCourse = true; // Show the "no maintenance" message
      }
    } catch (error) {
      console.error('Error fetching maintenance data:', error);
      this.noMaintenanceInCourse = true; // Show the "no maintenance" message
    }
  }

  goBack() {
    this.navCtrl.back();
  }

  capitalizeFirstLetter(val: string) {
    return String(val).charAt(0).toUpperCase() + String(val).slice(1);
  }
}

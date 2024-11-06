import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import { NavController, AlertController } from '@ionic/angular';

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

  constructor(private userService: UserService, private navCtrl: NavController,) { }

  async ngOnInit() {
    const sessionData = await this.userService.getUserSession();

    if (sessionData) {
      this.token = sessionData.token;
      this.user = sessionData.user;
      this.username = this.capitalizeFirstLetter(this.user.name);

      console.log('Token:', this.token);
      console.log('User Info:', this.user);
    } else {
      console.log('No se encontraron datos de sesión.');
    }
  }

  goBack() {
    this.navCtrl.back();
  }

  capitalizeFirstLetter(val: string) {
    return String(val).charAt(0).toUpperCase() + String(val).slice(1);
  }

}

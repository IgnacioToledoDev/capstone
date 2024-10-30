import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import { ManteciService } from 'src/app/services/manteci.service';

@Component({
  selector: 'app-home-mecanico',
  templateUrl: './home-mecanico.page.html',
  styleUrls: ['./home-mecanico.page.scss'],
})
export class HomeMecanicoPage implements OnInit {
  eventos: { nombre: string; hora: string; patente: string }[] = [];
  token: string | null = null;
  user: any = {};
  calendar: any[] = [];
  currentUser: any = {};

  constructor(private userService: UserService, private manteciService: ManteciService) {}

  async ngOnInit() {
    const sessionData = await this.userService.getUserSession();

    if (sessionData) {
      this.token = sessionData.token;
      this.user = sessionData.user;

      console.log('Token:', this.token);
      console.log('User Info:', this.user);
    } else {
      console.log('No se encontraron datos de sesión.');
    }


    const data = await this.manteciService.getMaintenanceCalendar();
    if (data) {
      this.calendar = data.calendar;
      this.currentUser = data.current[0]; 
    } else {
      console.error('No se pudo obtener el calendario de mantenimiento.');
    }
  }
}

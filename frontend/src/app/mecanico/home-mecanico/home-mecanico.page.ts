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
  currentUser: any = {};  // User object to store the current client
  currentList: any[] = [];  // To store the current maintenance records list

  constructor(private userService: UserService, private manteciService: ManteciService) {}

  async ngOnInit() {
    const sessionData = await this.userService.getUserSession();
    if (sessionData) {
      this.token = sessionData.token;
      this.user = sessionData.user;
    }

    const data = await this.manteciService.getMaintenanceCalendar();
    if (data) {
      this.calendar = data.calendar;
      this.currentList = data.current; // Store the current maintenance records
      this.currentUser = this.currentList[0] ? this.currentList[0].client : null; // Example for the first client
      console.log(data)
    } else {
      console.error('No se pudo obtener el calendario de mantenimiento.');
    }
  }
}

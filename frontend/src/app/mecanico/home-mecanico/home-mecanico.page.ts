import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import { ManteciService } from 'src/app/services/manteci.service';
import { Storage } from '@ionic/storage-angular';
import { Router } from '@angular/router';

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
  currentList: any[] = [];

  constructor(
    private userService: UserService,
    private manteciService: ManteciService,
    private storage: Storage,
    private router: Router
  ) {}

  async ngOnInit() {
    const sessionData = await this.userService.getUserSession();
    if (sessionData) {
      this.token = sessionData.token;
      this.user = sessionData.user;
    }

    const data = await this.manteciService.getMaintenanceCalendar();
    if (data) {
      this.calendar = data.calendar;
      this.currentList = data.current;
      this.currentUser = this.currentList[0] ? this.currentList[0].client : null;
    } else {
      console.error('No se pudo obtener el calendario de mantenimiento.');
    }
  }

  async saveMaintenanceIdAndNavigate(maintenanceId: number) {
    await this.storage.set('idmantesion', maintenanceId);
    this.router.navigate(['/mecanico/info-ser-cli']); // Change to your target route
  }
}

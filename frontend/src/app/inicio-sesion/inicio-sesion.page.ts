import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { UserService } from 'src/app/services/user.service';
import { NavController, AlertController } from '@ionic/angular';

@Component({
  selector: 'app-inicio-sesion',
  templateUrl: './inicio-sesion.page.html',
  styleUrls: ['./inicio-sesion.page.scss'],
})
export class InicioSesionPage implements OnInit {
  loginForm!: FormGroup;

  constructor(
    private fb: FormBuilder,
    private userService: UserService,
    private navCtrl: NavController,
    private alertController: AlertController
  ) {}

  ngOnInit() {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required]],
    });
  
    this.checkIfAuthenticated();
  }

  async onSubmit() {
    if (this.loginForm.valid) {
      const { email, password } = this.loginForm.value;
      try {
        const response = await this.userService.login({ email, password });
        console.log('Inicio de sesión exitoso:', response);
        this.navCtrl.navigateForward('/home');  // Redirige a la página de Home al terminar 
      } catch (error) {
        console.error('Error de inicio de sesión:', error);
        this.presentAlert('Error de inicio de sesión', 'Correo o contraseña incorrectos');
      }
    }
  }

  async presentAlert(header: string, message: string) {
    const alert = await this.alertController.create({
      header,
      message,
      buttons: ['OK'],
    });
    await alert.present();
  }

  async checkIfAuthenticated() {
    const isAuthenticated = await this.userService.checkAuthenticated();
    if (isAuthenticated) { // Recordar que es nesesario definir el tipo de USER 
      this.navCtrl.navigateForward('/home'); // Redirige a la página de Home si esta checkAuthenticated 
    }
  }
}
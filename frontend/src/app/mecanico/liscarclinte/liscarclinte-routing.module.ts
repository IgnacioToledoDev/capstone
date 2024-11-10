import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LiscarclintePage } from './liscarclinte.page';

const routes: Routes = [
  {
    path: '',
    component: LiscarclintePage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class LiscarclintePageRoutingModule {}

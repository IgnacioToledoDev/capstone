import { ComponentFixture, TestBed } from '@angular/core/testing';
import { LisReservasPage } from './lis-reservas.page';

describe('LisReservasPage', () => {
  let component: LisReservasPage;
  let fixture: ComponentFixture<LisReservasPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(LisReservasPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ReservaPage } from './reserva.page';

describe('ReservaPage', () => {
  let component: ReservaPage;
  let fixture: ComponentFixture<ReservaPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(ReservaPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
